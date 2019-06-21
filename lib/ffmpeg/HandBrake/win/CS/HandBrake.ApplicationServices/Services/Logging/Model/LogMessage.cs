﻿// --------------------------------------------------------------------------------------------------------------------
// <copyright file="LogMessage.cs" company="HandBrake Project (http://handbrake.fr)">
//   This file is part of the HandBrake source code - It may be used under the terms of the GNU General Public License.
// </copyright>
// <summary>
//   The message.
// </summary>
// --------------------------------------------------------------------------------------------------------------------

namespace HandBrake.ApplicationServices.Services.Logging.Model
{
    /// <summary>
    /// The json message.
    /// </summary>
    public class LogMessage
    {
        /// <summary>
        /// Initializes a new instance of the <see cref="LogMessage"/> class.
        /// </summary>
        /// <param name="content">
        /// The content.
        /// </param>
        /// <param name="messageType">
        /// The message type.
        /// </param>
        /// <param name="logLevel">
        /// The log level.
        /// </param>
        public LogMessage(string content, LogMessageType messageType, LogLevel logLevel)
        {
            this.Content = content;
            this.MessageType = messageType;
            this.LogLevel = logLevel;
        }

        /// <summary>
        /// Gets the content.
        /// </summary>
        public string Content { get; private set; }

        /// <summary>
        /// Gets a value indicating whether this message was generated by the GUI.
        /// If false, it was provided by libhb.
        /// </summary>
        public LogMessageType MessageType { get; private set; }

        /// <summary>
        /// Gets the log level.
        /// </summary>
        public LogLevel LogLevel { get; private set; }
    }
}
