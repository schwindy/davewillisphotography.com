﻿// --------------------------------------------------------------------------------------------------------------------
// <copyright file="JsonState.cs" company="HandBrake Project (http://handbrake.fr)">
//   This file is part of the HandBrake source code - It may be used under the terms of the GNU General Public License.
// </copyright>
// <summary>
//   The hand brake state.
// </summary>
// --------------------------------------------------------------------------------------------------------------------

namespace HandBrake.ApplicationServices.Interop.Json.State
{
    /// <summary>
    /// The hand brake state.
    /// </summary>
    public class JsonState
    {
        /// <summary>
        /// Gets or sets the scanning.
        /// </summary>
        public Scanning Scanning { get; set; }

        /// <summary>
        /// Gets or sets the working.
        /// </summary>
        public Working Working { get; set; }

        /// <summary>
        /// Gets or sets the work done.
        /// </summary>
        public WorkDone WorkDone { get; set; }

        /// <summary>
        /// Gets or sets the state.
        /// </summary>
        public int State { get; set; }
    }
}